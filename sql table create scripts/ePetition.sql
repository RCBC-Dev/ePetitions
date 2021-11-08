USE [PHPLogin_Dev]
GO
	/****** Object:  Table [dbo].[ePetition]    Script Date: 17/09/2021 08:37:45 ******/
SET
	ANSI_NULLS ON
GO
SET
	QUOTED_IDENTIFIER ON
GO
	CREATE TABLE [dbo].[ePetition](
		[petid] [int] IDENTITY(1, 1) NOT NULL,
		[userid] [int] NOT NULL,
		[title] [nvarchar](600) NOT NULL,
		[detail] [nvarchar](4000) NOT NULL,
		[petitioncreated] [datetime] NOT NULL,
		[petitionapproved] [datetime] NULL,
		[petitiondisabled] [datetime] NULL,
		[petitionstatus] [nvarchar](25) NULL,
		[disabledreason] [nvarchar](50) NULL
	) ON [PRIMARY]
GO