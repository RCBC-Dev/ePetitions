USE [PHPLogin_Dev]
GO
	/****** Object:  Table [dbo].[ePetAudit]    Script Date: 17/09/2021 08:37:31 ******/
SET
	ANSI_NULLS ON
GO
SET
	QUOTED_IDENTIFIER ON
GO
	CREATE TABLE [dbo].[ePetAudit](
		[auditid] [int] IDENTITY(1, 1) NOT NULL,
		[userid] [int] NOT NULL,
		[action] [nvarchar](50) NOT NULL,
		[actiondatetime] [datetime] NOT NULL
	) ON [PRIMARY]
GO